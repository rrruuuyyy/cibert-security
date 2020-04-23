<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Simple report</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.8.2/css/bulma.css">

</head>

<body>
	<div class="w-100 has-text-centere">
		<h2>Simple report</h2>
	</div>
	<!-- Display Validation Errors -->
	{{-- @include('common.errors') --}}
	<div class="row">
		<div class="col-12">
			<h4 class="has-text-centere">Infected domains</h4>
			<table class="table table is-striped">
				<thead>
					<tr>
						<th>Domain</th>
                        <th>Name</th>
                        <th>User</th>
                        <th>Action taken</th>
                        <th>Status</th>
                        <th>Infections</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($domains as $domain)
						<tr>
							<td>{{$domain->url}}</td>
							<td>{{$domain->name}}</td>
							<td>{{$domain->user->name}}</td>
							<td>{{ ( count($domain->actions_takens) != 0 )? $domain->actions_takens[0]->type  : 'None' }}</td>
							<td>{{$domain->status}}</td>
							<td>{{count($domain->infections)}}</td>
						</tr>
            		@endforeach
				</tbody>
			</table>
		</div>
	</div>

</body>

<style>
	.w-100{
		background-color: red;
	}
</style>

</html>