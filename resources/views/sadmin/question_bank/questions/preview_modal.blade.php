<div class="modal-header d-flex justify-content-between align-items-center">
    <h5 class="modal-title fs-16 fw-600 text-textBlack">{{ __('Question Preview') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-20">
    <div class="mb-15">
        <h6 class="fs-14 fw-600 text-textBlack mb-5">{{ __('Question:') }}</h6>
        <div class="fs-14 text-para-text">{!! $question->question_text !!}</div>
    </div>

    @if($question->image)
    <div class="mb-15">
        <img src="{{ asset($question->image) }}" alt="Question Image" class="img-fluid rounded" style="max-height: 200px;">
    </div>
    @endif

    <div class="mb-15">
        <h6 class="fs-14 fw-600 text-textBlack mb-5">{{ __('Type:') }}</h6>
        <div class="fs-14 text-para-text">{{ $question->questionType ? $question->questionType->name : 'N/A' }}</div>
    </div>

    <div class="mb-15">
        <h6 class="fs-14 fw-600 text-textBlack mb-10">{{ __('Answer / Options:') }}</h6>
        @php
            $type = (int) $question->question_type_id;
        @endphp

        @if($type === QB_QTYPE_MCQ)
            <ul class="list-group">
            @foreach($question->options as $index => $opt)
                <li class="list-group-item d-flex align-items-center {{ $opt->is_correct ? 'list-group-item-success' : '' }}">
                    <span class="fw-bold me-2">{{ chr(65 + $index) }}.</span> 
                    {{ $opt->option_text }}
                    @if($opt->is_correct)
                        <i class="fa fa-check text-success ms-auto"></i>
                    @endif
                </li>
            @endforeach
            </ul>

        @elseif($type === QB_QTYPE_TRUE_FALSE || $type === QB_QTYPE_SHORT || $type === QB_QTYPE_LONG)
            <div class="p-10 bg-light rounded fs-14 fw-500 text-success">
                {{ $question->correct_answer }}
            </div>

        @elseif($type === QB_QTYPE_FILL_BLANK)
            @php
                $optionsData = json_decode($question->options_json, true);
                $blanks = $optionsData['blanks'] ?? [];
            @endphp
            @if(count($blanks) > 0)
                <ol>
                    @foreach($blanks as $blank)
                        <li class="fs-14 text-success fw-500">{{ $blank }}</li>
                    @endforeach
                </ol>
            @else
                <div class="text-danger">{{ __('No blanks data found.') }}</div>
            @endif

        @elseif($type === QB_QTYPE_MATCHING)
            @php
                $optionsData = json_decode($question->options_json, true);
                $matches = $optionsData['matches'] ?? [];
            @endphp
            @if(count($matches) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('Left Side') }}</th>
                                <th>{{ __('Right Side (Match)') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matches as $match)
                            <tr>
                                <td>{{ $match['left'] }}</td>
                                <td class="text-success fw-500">{{ $match['right'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-danger">{{ __('No matching data found.') }}</div>
            @endif
            
        @else
            <!-- Fallback -->
            <div class="fs-14 text-para-text">{{ $question->correct_answer ?? __('No answer defined.') }}</div>
        @endif
    </div>

    @if($question->explanation)
    <div class="mb-15">
        <h6 class="fs-14 fw-600 text-textBlack mb-5">{{ __('Explanation:') }}</h6>
        <div class="fs-14 text-para-text p-10 bg-light rounded">{!! $question->explanation !!}</div>
    </div>
    @endif
</div>
