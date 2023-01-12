

@extends('layouts.admin')

@section('content')
   
<div class="row">
	<div class="col-lg-12">
        <div class="card mb-4">
			<div class="card-body p-4">
			<div class="row">
				<div class="col">
				<div class="card-title fs-4 fw-semibold">Users</div>
				<div class="card-subtitle text-disabled mb-4">1.232.150 registered users</div>
				</div>
				<div class="col-auto ms-auto">
					<button class="btn btn-secondary">
					<svg class="icon me-2">
					<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user-plus"></use>
					</svg>Add new user
					</button>
				</div>
			</div>


			<div class="table-responsive">

					<div class="container">
						<form action="" class="m-auto" >
							{{ csrf_field() }}
							<h3 class="my-4">Contact Form</h3>
							<hr class="my-4" />
							<div class="form-group mb-3 row">
								<label for="your-name2" class="col-md-5 col-form-label">Your Name</label>
								<div class="col-md-7">
									<input type="text" class="form-control form-control-lg" id="your-name2" name="your-name" required>
								</div>
							</div>
							<div class="form-group mb-3 row">
								<label for="your-email3" class="col-md-5 col-form-label">Your Email</label>
								<div class="col-md-7">
									<input type="email" class="form-control form-control-lg" id="your-email3" name="your-email" required>
									<small class="form-text text-muted"> Please enter a valid email address</small>
								</div>
							</div>
							<hr class="bg-transparent border-0 py-1" />
							
							<div class="form-group mb-3 row">
								<label for="your-message6" class="col-md-5 col-form-label">Your Message</label>
								<div class="col-md-7">
									<textarea class="form-control form-control-lg" id="your-message6" name="your-message" required></textarea>
								</div>
							</div>
							
							<hr class="my-4" />
							
							<div class="form-group mb-3 row">
								<label for="send-a-message8" class="col-md-5 col-form-label"></label>
								<div class="col-md-7">
									<button class="btn btn-primary btn-lg" type="submit">Send a Message!</button>
								</div>
							</div>

						</form>
					</div>




			</div>
        </div>
	</div>    
</div>





@stop