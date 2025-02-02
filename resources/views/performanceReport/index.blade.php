<x-app-layout>
    <div class="container mt-5">
        @if (session('success'))
            <div id="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="text-center my-4" style="font-size: 23px; font-weight: bold;">Generate Performance Report</h4>
                <hr class="mb-4">

                <form method="post" action="{{ route('performanceReport.generate') }}">
                    @csrf

                    <div class="row justify-content-between">
                        <!-- Report Type Selection -->
                        <div class="mb-4 col-md-6">
                            <x-input-label for="report_type" :value="__('Report Type')" />
                            <select id="report_type" name="report_type" class="form-control" required>
                                <option value="" disabled selected>{{ __('Select Report Type') }}</option>
                                <option value="month">{{ __('Monthly') }}</option>
                                <option value="year">{{ __('Yearly') }}</option>
                            </select>
                        </div>

                        <!-- Year Selection -->
                        <div class="mb-4 col-md-6">
                            <x-input-label for="year" :value="__('Year')" />
                            <select id="year" name="year" class="form-control" required>
                                @foreach (range(date('Y'), date('Y') - 10) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Month Selection (Hidden by Default) -->
                    <div class="mb-4" id="month-container" style="display: none;">
                        <x-input-label for="month" :value="__('Month')" />
                        <select id="month" name="month" class="form-control">
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Employee Selection Type -->
                    <div class="mb-4">
                        <x-input-label for="employee_selection" :value="__('Employee Selection')" />
                        <select id="employee_selection" name="employee_selection" class="form-control" required>
                            <option value="all">All Employees</option>
                            <option value="selected">Selected Employees</option>
                        </select>
                    </div>

                    <!-- Employee Dropdown (Hidden by Default) -->
                    <div class="mb-4" id="employee-container" style="display: none; width: 100%;">
                        <x-input-label for="employee_ids" :value="__('Select Employees')" />
                        <select id="employee_ids" name="employee_ids[]" class="form-control select2" multiple style="width: 100%;">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Generate Report') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include jQuery & Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <script>
        $(document).ready(function() {
            // Initialize Select2 for employee dropdown
            $('.select2').select2({
                placeholder: "Search and select employees",
                allowClear: true
            });

            // Show/hide month selection based on report type
            $('#report_type').on('change', function () {
                if ($(this).val() === 'month') {
                    $('#month-container').slideDown();
                } else {
                    $('#month-container').slideUp();
                }
            });

            // Show/hide employee selection based on choice
            $('#employee_selection').on('change', function () {
                if ($(this).val() === 'selected') {
                    $('#employee-container').slideDown();
                } else {
                    $('#employee-container').slideUp();
                }
            });
        });
    </script>
</x-app-layout>
