<h5><b>Round Evaluation Parameters</b></h5>
<ul class="list-group round_evaluation_list my-3 border rounded">
@foreach($round->evaluationParameters as $evaluationParameter)
	<li class="list-group-item">
		<div><b>{{ $evaluationParameter->name }}</b></div>
		@php
			$roundEvaluation = $evaluation->where('evaluation_id', $evaluationParameter->id)->first();
		@endphp
		@foreach($evaluationParameter->options as $option)
			@php
				$roundEvaluationOption = $roundEvaluation ? $roundEvaluation->where('option_id', $option->id)->first() : null;
			@endphp

		    <div class="form-check form-check-inline">
		      <input class="form-check-input" type="radio" name="round_evaluation[{{ $evaluationParameter->id }}][option_id]" id="inlineRadio1" value="{{ $option->id }}" {{ sizeof($roundEvaluationOption) > 0 ? "checked" : "" }}>
		      <input type="hidden" name="round_evaluation[{{ $evaluationParameter->id }}][evaluation_id]" value="{{ $evaluationParameter->id }}">
		      <label class="form-check-label" for="{{ $evaluationParameter->name }}">{{ $option->value }}</label>
		    </div>
	    @endforeach
	    <input type="text" name="round_evaluation[{{ $evaluationParameter->id }}][comment]" placeholder="Comment" class="form-control" value="{{ $roundEvaluation ? $roundEvaluation->comment : "" }}">
	    <br>
	</li>
@endforeach
</ul>