<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">Désignation</th>
            <th class="whitespace-no-wrap text-center">Nombres de permissions</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
        <tr class="tr">
            <td class="td">{{ $data[$i]["name"]}}</td>
            <td class="td">
                @if($data[$i]["permissions"])
                {{ count($data[$i]["permissions"])}}
                @endif
                @if(!$data[$i]["permissions"])
                0
                @endif
            </td>
        </tr>
        @endfor
    </table>
</div>
