@component('mail::message')
# New generation

The body of your message.<br>
Welcome {{$user->last_name}}<br>
Your OTP is <strong style="color:'blue'">{{$OTP}}</strong>  will be expired for 60 seconds
{{-- @component('mail::button')
Button Text
@endcomponent --}}

Thanks,<br>
<strong style="color:'red'">Hikky</strong>
@endcomponent