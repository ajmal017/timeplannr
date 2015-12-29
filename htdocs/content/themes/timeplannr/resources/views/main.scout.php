@include('headernew')

<div class="container-dummy" id="main" style="padding: 10px 20px;">

	@yield('main')

</div>

<div class="sidebar">

	@section('sidebar')

	<p>Sidebar section from the "main" layout file.</p>

	@show

</div>

@include('footer')