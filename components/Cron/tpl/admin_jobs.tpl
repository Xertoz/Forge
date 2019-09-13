<section class="content-header">
	<h1>{'Cron jobs'|l}</h1>
	<ol class="breadcrumb">
		<li class="active"><i class="glyphicon glyphicon-cog"></i> {'Cron jobs'|l}</li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="box box-primary">
		<div class="box-header"><h3 class="box-title">{'Scheduled jobs'|l}</h3></div>
		<div class="box-body">
			{if $jobs}
				{$jobs}
			{else}
				<p><i>{'No available cron jobs were found in your website.'|l}</i></p>
			{/if}
		</div>
	</div>
	<div class="box">
		<div class="box-header"><h3 class="box-title">{'Log'|l}</h3></div>
		<div class="box-body">
			<p>{'No cron jobs have been executed yet'|l}</p>
		</div>
	</div>
</section>