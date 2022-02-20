
@if(app()->getLocale() == 'ar')
    <option value="">اختر خطة اعلانية</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->title_ar}}</option>
    @empty
        <option disabled selected=""> لا يوجد خطط اعلانية حتى الأن </option>
    @endforelse
@else
    <option value="">Choose an advertising plan</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->title_en}}</option>
    @empty
        <option disabled selected="">There are no advertising plans yet</option>
    @endforelse
@endif
