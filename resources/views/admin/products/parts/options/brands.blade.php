
@if(app()->getLocale() == 'ar')
    <option value="">اختر الماركة</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->value_ar}}</option>
    @empty
        <option disabled selected=""> لا يوجد ماركات حتى الأن </option>
    @endforelse
@else
    <option value="">choose brand</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->value_en}}</option>
    @empty
        <option disabled selected=""> no brands until now</option>
    @endforelse
@endif
