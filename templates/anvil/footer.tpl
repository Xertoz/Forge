			</div>
			<div id="footer">
				<span>
					{if $forge.isAdmin}<a href="/admin">&raquo; {'Administration'|l}</a>{/if}
					{if isset($page)}{$page->page_updated}{else}{date('Y-m-d H:i:s')}{/if}
				</span>
			</div>
		</div>
	</body>
</html>
