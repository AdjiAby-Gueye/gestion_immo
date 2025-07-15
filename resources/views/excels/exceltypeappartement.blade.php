<div>
    <table class="table mb-20">
        <tr class="tr">


            <th class="whitespace-no-wrap">Designation</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)>
            <tr class="tr">

                <td class="td">{{ $data[$i]["designation"]}}</td>

            </tr>
            @endfor
    </table>
</div>