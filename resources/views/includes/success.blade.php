@if (session('alertSuccess'))
   <div class="alert alert-primary alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session('alertSuccess') }}
    </div>
@endif
