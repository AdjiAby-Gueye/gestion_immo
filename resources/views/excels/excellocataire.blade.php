<div>
    <table class="table mb-20">
        <tr class="tr">
            <th class="whitespace-no-wrap text-center">N°</th>
            <th class="whitespace-no-wrap text-center">Nom & Prenom</th>
            <th class="whitespace-no-wrap text-center">Email</th>
            <th class="whitespace-no-wrap text-center">Profession</th>
            <th class="whitespace-no-wrap text-center">Telephone 1</th>
            <th class="whitespace-no-wrap text-center">Telephone 2</th>
        </tr>

        @for ($i = 0; $i < count($data); $i++)
        <tr class="tr">
        <td class="td">{{ $i+1 }}</td>
        <td class="td">{{$data[$i]["prenom"]}} {{ $data[$i]["nom"]}}</td>
        <td class="td">{{$data[$i]["email"]}}</td>
        <td class="td">{{$data[$i]["profession"]}}</td>
        <td class="td">{{$data[$i]["telephoneportable1"]}}</td>
        <td class="td">{{$data[$i]["telephoneportable2"]}}</td>

        </tr>
        @endfor
    </table>
</div>
