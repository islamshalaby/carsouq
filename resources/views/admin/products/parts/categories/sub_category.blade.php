
@if(app()->getLocale() == 'ar')
    <option value="">{{ __('messages.choose_sub_category') }}</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->title_ar}}</option>
    @empty
        <option disabled selected=""> لا يوجد أقسام فرعية حتى الأن </option>
    @endforelse
@else
    <option value="">{{ __('messages.choose_sub_category') }}</option>
    @forelse($data as $row)
        <option value="{{$row->id}}">{{$row->title_en}}</option>
    @empty
        <option disabled selected=""> no sub category until now</option>
    @endforelse
@endif
