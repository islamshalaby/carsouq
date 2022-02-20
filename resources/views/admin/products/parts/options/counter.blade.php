
@if(app()->getLocale() == 'ar')
    <option value="">اختر العداد</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->value_ar}}</option>
    @empty
        <option disabled selected=""> لا يوجد عدادات حتى الأن </option>
    @endforelse
@else
    <option value="">choose speed counter</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->value_en}}</option>
    @empty
        <option disabled selected=""> no speed counter until now</option>
    @endforelse
@endif
