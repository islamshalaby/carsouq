
@if(app()->getLocale() == 'ar')
    <option value="">اختر سنة الموديل</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->value_ar}}</option>
    @empty
        <option disabled selected=""> لا يوجد سنوات للموديل حتى الأن </option>
    @endforelse
@else
    <option value="">choose model year</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->value_en}}</option>
    @empty
        <option disabled selected=""> no model year until now</option>
    @endforelse
@endif
