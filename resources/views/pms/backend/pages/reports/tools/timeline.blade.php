<div class="row">
    <div class="col-md-4">
        <label for="timeline"><strong>Timeline</strong></label>
        <select name="timeline" id="timeline" class="form-control select2bs4" onchange="getDateTimelines()">
            <option value="range" {{ request()->get('timeline') == 'range' ? 'selected' : '' }}>Range</option>
            <option value="weekly" {{ request()->get('timeline') == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ request()->get('timeline') == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ request()->get('timeline') == 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>
    <div class="col-md-8">
        <div class="row timelines timeline-range">
            <div class="col-md-6">
                <label for="from"><strong>From</strong></label>
                <input type="date" name="from" id="from" value="{{ strtotime(request()->get('from')) > 0 ? request()->get('from') : date('Y-m-01') }}" class="form-control" placeholder="From">
            </div>
            <div class="col-md-6">
                <label for="to"><strong>To</strong></label>
                <input type="date" name="to" id="to" value="{{ strtotime(request()->get('to')) > 0 ? request()->get('to') : date('Y-m-t') }}" class="form-control" placeholder="To">
            </div>
        </div>
        <div class="row timelines timeline-weekly" style="display: none">
            <div class="col-md-4">
                <label for="week_year"><strong>Year</strong></label>
                <select name="week_year" id="week_year" class="form-control select2bs4">
                    @for($i=2024;$i<=2050;$i++)
                    <option {{ request()->get('week_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label for="week_month"><strong>Month</strong></label>
                <select name="week_month" id="week_month" class="form-control select2bs4">
                    @for($i=1;$i<=12;$i++)
                    <option value="{{ $i<10 ? '0'.$i : $i }}" {{ request()->get('week_month') == ($i<10 ? '0'.$i : $i) ? 'selected' : '' }}>{{ date('F', strtotime(date('Y').'-'.($i<10 ? '0'.$i : $i))) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label for="week"><strong>Week</strong></label>
                <select name="week" id="week" class="form-control select2bs4">
                    @for($i=0;$i<4;$i++)
                    <option value="{{ $i }}" {{ request()->get('week') == $i ? 'selected' : '' }}>{{ ($i+1).ordinal($i+1).' week' }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="row timelines timeline-monthly" style="display: none">
            <div class="col-md-6">
                <label for="month_year"><strong>Year</strong></label>
                <select name="month_year" id="month_year" class="form-control select2bs4">
                    @for($i=2024;$i<=2050;$i++)
                    <option {{ request()->get('month_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-6">
                <label for="month"><strong>Month</strong></label>
                <select name="month" id="month" class="form-control select2bs4">
                    @for($i=1;$i<=12;$i++)
                    <option value="{{ $i<10 ? '0'.$i : $i }}" {{ request()->get('month') == ($i<10 ? '0'.$i : $i) ? 'selected' : '' }}>{{ date('F', strtotime(date('Y').'-'.($i<10 ? '0'.$i : $i))) }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="row timelines timeline-yearly" style="display: none">
            <div class="col-md-6">
                <label for="year_from"><strong>From Year</strong></label>
                <select name="year_from" id="year_from" class="form-control select2bs4">
                    @for($i=2024;$i<=2050;$i++)
                    <option {{ request()->get('year_from') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-6">
                <label for="year_to"><strong>To Year</strong></label>
                <select name="year_to" id="year_to" class="form-control select2bs4">
                    @for($i=2024;$i<=2050;$i++)
                    <option {{ request()->get('year_to') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
</div>