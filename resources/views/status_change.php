<?php

use App\Models\PmsModels\Requisition;
use App\Models\PmsModels\RequisitionTracking;
use Illuminate\Http\Request;

public function toggleRequisitionStatus(Request $request)
{
    $requisition = Requisition::where('id', $request->id)->first();
    if (isset($requisition->id)) {
        $newStatus = $request->status;
        $newText = $newStatus == 1 ? 'Acknowledge' : (($newStatus == 2) ? 'Halt' : 'Pending');
        $newApproved = $newStatus == 1 ? 1 : (($newStatus == 2) ? 1 : Null);
        $newMessage = $newStatus == 1 ? 'Successfully Updated To Acknowledgement' : (($newStatus == 2) ? 'Successfully Updated To Halt' : (($newStatus == 0) ? 'Successfully Send to ' . (auth()->user()->hasRole('Department-Head') ? 'SBU head' : (auth()->user()->hasRole('SBU Head') ? 'Management' : 'Department head')) : 'Successfully Updated To Pending'));
        
        //When resend requisition
        if ($requisition->status == 2) {
            //count check
            if (session()->get('system-information')['resend_limit'] > $requisition->resend_count) {
                //update resend count
                $requisition->resend_count = $requisition->resend_count + 1;
                $requisition->save();
                //store the existing data to log
                $this->resendRequisitionLogStore($requisition);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry!.Your resend limit is over. Please generate a new requisition'
                ]);
            }
        }
        
        $update = $requisition->update([
            'status' => $newStatus,
            'is_passed' => 'no',
            'approved_id' => $newApproved,
            'admin_remark' => $request->admin_remark,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => auth()->user()->id,
            'approved_by' => auth()->user()->id
        ]);
        if ($newStatus == 1) {
            
            //If approved then send to finance for approval.
            
            RequisitionTracking::storeRequisitionTracking($requisition->id, 'approved');

//                $message = '<span class="notification-links" data-src="' . getModules()['finance']->url . '/pms/store-manage/store-inventory-compare/' . $requisition->id . '" data-title="Requisition Details">Reference No:' . $requisition->reference_no . '.Watting for Finanace Approval.</span>';
//                CreateOrUpdateNotification($message, 'unread', '', getBulkManagerInfo('Accounts', $requisition->hr_unit_id), 'send-to-accounts');
//
            $message = '<span class="notification-links" data-src="' . getModules()['finance']->url . '/pms/store-manage/store-inventory-compare/' . $requisition->id . '" data-title="Requisition Details">Reference No:' . $requisition->reference_no . '.Watting for Finanace Approval.</span>';
            CreateOrUpdateNotification($message, 'unread', '', [$requisition->assigned_finance_id], 'send-to-accounts');
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated To Acknowledgement',
                'new_text' => $newText
            ]);
        }
        
        if ($newStatus == 0) {
            RequisitionTracking::storeRequisitionTracking($requisition->id, 'pending');
            $message = '<span class="notification-links" data-src="' . route('pms.requisition.list.view.show', $requisition->id) . '" data-title="Requisition Details">Requisition Reference No:' . $requisition->reference_no . '. Watting for approval.</span>';
            
            $receivers = [getDepartmentHead($requisition->author_id)];
            $receiver_slug = 'send-to-department-head';
            
            if (auth()->user()->hasRole('Department-Head')) {
                $receivers = getManagerInfo('SBU Head', auth()->user()->employee->as_unit_id, true);
                $receiver_slug = 'send-to-sbu-head';
            }
            
            if (auth()->user()->hasRole('SBU Head')) {
                $receivers = getManagerInfo('Management', auth()->user()->employee->as_unit_id, true);
                $receiver_slug = 'send-to-managment';
            }
            
            CreateOrUpdateNotification($message, 'unread', '', $receivers, $receiver_slug);
        }
        
        if ($update) {
            return response()->json([
                'success' => true,
                'message' => $newMessage,
                'new_text' => $newText
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Something Went Wrong!'
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Data not found!'
    ]);
}

