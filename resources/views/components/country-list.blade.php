<div class="inline-block mt-2 relative w-full"  class="">
    <select class="block select2 appearance-none w-full bg-white text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline "
    id="{!! $idName !!}" name="{!! $name !!}" >
        <option value="" class="">Pays de résidence</option>
        @foreach ($countries as $country)
            <option value="{!! $country['name'] !!}">
               {!! $country['name'] !!} {!! $country['flag'] !!} 
            </option>
        @endforeach

    </select>
</div>