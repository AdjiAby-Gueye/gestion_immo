@extends('pdfs.layouts.layout')
@section('content')
<h2 class="text-center text-uppercase">{{$title}}</h2>

<div class="ph20">
    <h4 class="text-uppercase">
        <u>Traité(s):</u> <strong>{{$totals['toUpload']}}</strong><br><br>
        <u>Importé(s):</u> <strong>{{$totals['upload']}}</strong><br><br>
        <u>Non importé(s):</u> <strong>{{ ($totals['toUpload'] - $totals['upload']) }}</strong><br><br>
    </h4>
</div>

@if(count($reports) > 0)
<table class="table">
    <thead>
    <tr class="tr">
        <th class="th text-center">Ligne</th>
        <th class="th text-center">Libellé</th>
        <th class="th text-center">Cause</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reports as $report)
    <tr class="tr">
        <td class="td">{{$report['ligne']}}</td>
        <td class="td">{{$report['libelle']}}</td>
        <td class="td">
            @if(!empty($report['erreur']))
            <span>{{$report['erreur']}}</span>
            @else
            <strong>-</strong>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endif
@endsection
