
@if(app()->getLocale() == 'ar')
    <option value="">اختر نوع الماركة</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->value_ar}}</option>
    @empty
        <option disabled selected=""> لا يوجد أنواع المركات حتى الأن </option>
    @endforelse
@else
    <option value="">choose brand type</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->value_en}}</option>
    @empty
        <option disabled selected=""> no brand types until now</option>
    @endforelse
@endif
