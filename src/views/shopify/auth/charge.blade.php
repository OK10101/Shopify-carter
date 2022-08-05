@extends('carter::shopify.escape_iframe')

@section('script')
    <script type="text/javascript">
        window.top.location.href = '{{ $redirect }}';
    </script>
@endsection