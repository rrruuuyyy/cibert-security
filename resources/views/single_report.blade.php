<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Simple report</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.8.2/css/bulma.css">
</head>

<body class="margenes" style="display:block;">
	<div class="w-100 centrado">
		<h1 class="title is-3">Domain abuse report</h1>
	</div>
	<div class="w-100 text-section">
		<h4 class="title is-5">Details report</h4>
	</div>
	<div class="separador">
	</div>
	<div class="w-100">
		<span>TLD monitored: <?php echo $user->tld?></span>
		<br>
		<span>Total monitored domains: <?php echo $user->total_monitoring?></span>
		<br>
		<span>Montitoring service started: <?php echo $user->monitoring?></span>
	</div>
	<div>
	</div>
	<div class="w-100 text-section">
		<h4 class="title is-5">Details report</h4>
	</div>
	<div class="separador">
	</div>
	<?php if( count($domains) != 0 ){
		?>
		<table class="table is-striped" style="width:100%; margin-top:10px;">
			<thead>
				<tr>
					<th>Domain</th>
					<th>User</th>
					<th>Action taken</th>
					<th>Date</th>
					<th>Status</th>
					<th>Action taken2</th>
					<th>Date</th>
					<th>Infections</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($domains as $domain)
					<tr>
						<td>{{$domain->url}}</td>
						<td>{{ empty($domain->user->name) ? 'No user' : $domain->user->name }}</td>
						<td>{{ ( count($domain->actions_takens) != 0 )? $domain->actions_takens[0]->type  : 'None' }}</td>
						<td>{{ ( count($domain->actions_takens) != 0 )? $domain->actions_takens[0]->created_at  : 'None' }}</td>
						<td>{{$domain->status}}</td>
						<td>{{ ( count($domain->actions_takens_domain) != 0 )? $domain->actions_takens_domain[0]->type  : 'None' }}</td>
						<td>{{ ( count($domain->actions_takens_domain) != 0 )? $domain->actions_takens_domain[0]->created_at  : 'None' }}</td>
						<td>{{count($domain->infections)}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<?php
	}else{ ?>
		<div class="notification">
			<strong>No infected dominions</strong>
		</div> <?php
	}?>
	<div class="w-100 text-section">
		<h4 class="title is-5">Abuse distribution type</h4>
	</div>
	<div class="separador">
	</div>
	<div class="w-100" style="display:block; height: 10px;">
	</div>
	<div class="w-100" style="display:block">        
		<div class="w-50 cuadro_infection" style="float: left;">
			<div class="w-20">
				<p class="text_centrado">
					Malware: <?php echo $porcents->malware; ?>%
				</p>
			</div>
            <div class="w-80" style="float: right;">
                <div class="progress-bar">
                    <span class="progress-bar-fill" style="width: <?php echo $porcents->malware; ?>%;"></span>
                </div>
            </div>
		</div>
		<div class="w-50 cuadro_infection" style="float: left;">
            <div class="w-20">
				<p class="text_centrado">
					Seo Spam: <?php echo $porcents->seo_spam; ?>%
				</p>
			</div>
            <div class="w-80" style="float: right;">
                <div class="progress-bar">
                    <span class="progress-bar-fill" style="width: <?php echo $porcents->seo_spam; ?>%;"></span>
                </div>
            </div>
		</div>
		<div class="w-50 cuadro_infection" style="float: left;">
			<div class="w-20">
				<p class="text_centrado">
					Pharming: <?php echo $porcents->pharming; ?>%
				</p>
			</div>
            <div class="w-80" style="float: right;">
                <div class="progress-bar">
                    <span class="progress-bar-fill" style="width: <?php echo $porcents->pharming; ?>%;"></span>
                </div>
            </div>
		</div>
		<div class="w-50 cuadro_infection" style="float: left;">
            <div class="w-20">
				<p class="text_centrado">
					Phising: <?php echo $porcents->phising; ?>%
				</p>
			</div>
            <div class="w-80" style="float: right;">
                <div class="progress-bar">
                    <span class="progress-bar-fill" style="width: <?php echo $porcents->phising; ?>%;"></span>
                </div>
            </div>
        </div>
		<div class="w-50 " style="float: left;">
            <div class="w-20">
				<p class="text_centrado">
					Black Hat Seo: <?php echo $porcents->black_hat; ?>%
				</p>
			</div>
            <div class="w-80" style="float: right;">
                <div class="progress-bar">
                    <span class="progress-bar-fill" style="width: <?php echo $porcents->black_hat; ?>%;"></span>
                </div>
            </div>
        </div>
	</div>
</body>



<div class="w3-border">
	<div class="w3-grey" style="height:24px;width:20%"></div>
</div>

<style>
	.espacio10{
		margin-top: 10px;
	}
	.text_centrado{
		text-align: center;
		vertical-align: middle;
		padding-left: 5px;
		padding-top: 5px;
		font-size: 10px;
	}
	.borde_loader{		
		width: 300px;
		height: 30px;
		/* width: 300px; */
		border: 1px solid black;
		
	}
	.section_one{
		display: inline;
		width: 100%;
	}
	.graf{
		position: relative;
		width: 40%;
		float:left;
	}
	body{
		display: block
	}
	.relative{
		position: relative;
	}
	.w-100{
		width:100%;
    	display: inline-block;
	}
	.w-50{
		/* background-color: blue; */
		display: inline-block;
		width: 50%;
	}
	.w-20{
		/* background-color: blue; */
		display: inline;
		width: 30%;
		display:inline-block;
		vertical-align: middle;
	}
	.w-80{
		/* background-color: blue; */
		display: inline;
		width: 70%;
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
		margin-top: 10px;
		padding-left: 5px
		margin-bottom: 5px;
	}
	.margenes{
		padding-left: 10px;
		padding-right: 10px;
		display: inline-block;
		width: 100%;
	}
	table{
		font-size: 12px;
		margin-top: 20px;
	}
	.progress-bar {
		width: 100%;
		background-color: #e0e0e0;
		padding: 3px;
		border-radius: 3px;
		box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
	}
	.progress-bar-fill {
		display: block;
		height: 22px;
		background-color: #659cef;
		border-radius: 3px;    
		transition: width 500ms ease-in-out;
	}
	.seccion_infection{
		padding-top: 10px;
		font-size: 14px;
	}
	.cuadro_infection{
		margin-bottom: 10px;
		font-size: 12px;
	}
</style>

</html>