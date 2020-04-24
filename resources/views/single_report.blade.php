<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Simple report</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.8.2/css/bulma.css">

</head>

<body class="margenes">
	<div class="w-100 centrado">
		<h1 class="title is-1">Simple report</h1>
	</div>
	<div class="w-100 text-section">
		<h4 class="title is-3">Infected domains</h4>
	</div>
	<div class="separador">
	</div>
	<table class="w-100 table is-striped">
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
					<td>{{ empty($domain->user->name) ? 'No user' : $domain->user->name }}</td>
					<td>{{ ( count($domain->actions_takens) != 0 )? $domain->actions_takens[0]->type  : 'None' }}</td>
					<td>{{$domain->status}}</td>
					<td>{{count($domain->infections)}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</body>

<style>
	.relative{
		position: relative;
	}
	.w-100{
		width: 100%
	}
	td{
		font-size: 14px;
	}
	.centrado{
		display: block;
		text-align: center;
	}
	.separador{
		width: 100%;
		padding: 10px;
		border-bottom: 1px solid black;
	}
	.title_principal{
		font-size: 20px;
		font-weight: bold;
	}
	.title_second{
		font-size: 17px;

	}
	.text-section{
		text-align: left;
		margin-top: 20px;
		padding-left: 5px
		margin-bottom: 5px;
	}
	.margenes{
		padding-left: 10px;
		padding-right: 10px;
	}
</style>

</html>