<div>
    <table class="table mb-20">
        <tr>
            <th class="whitespace-no-wrap text-center">N°</th>
            <th class="whitespace-no-wrap text-center">Nom utilisateur</th>
            <th class="whitespace-no-wrap text-center">Email</th>
            <th class="whitespace-no-wrap text-center">Profil</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
        <tr class="tr">
            <td class="td">{{ $i+1}}</td>
            <td class="td">{{ $data[$i]["name"]}}</td>
            <td class="td">{{ $data[$i]["email"]}}</td>
            <td class="td">
                @if($data[$i]["roles"] && count($data[$i]["roles"]) > 0)
                {{ $data[$i]["roles"][0]['name']}}
                @endif
            </td>
        </tr>
        @endfor
    </table>
</div>
