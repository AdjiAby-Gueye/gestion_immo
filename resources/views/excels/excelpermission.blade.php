<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap">Appellation</th>
            <th class="whitespace-no-wrap text-center">Description</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
        <tr class="tr">
            <td class="td">{{ $data[$i]["name"]}}</td>
            <td class="td">
                {{ $data[$i]["display_name"]}}
            </td>
        </tr>
        @endfor
    </table>
</div>
