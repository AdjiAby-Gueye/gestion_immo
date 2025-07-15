<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">N°</th>
            <th class="whitespace-no-wrap">Objet</th>
            <th class="whitespace-no-wrap">Contenue</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)>
            <tr class="tr">

                <td class="td">{{ $i+1 }}</td>
                <td class="td">{{ $data[$i]["objet"]}}</td>
                <td class="td">{{ $data[$i]["contenu"]}}</td>
            </tr>
            @endfor
    </table>

</div>
