		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		var con_height = $("#main-content").height();
		$(".sidebar").css("height",con_height);

		$("form").attr("autocomplete","off");

		$("select[name='example_length']").change(function(){
			var con_height = $("#main-content").height();
			$(".sidebar").css("height",con_height);
		});
	});
</script>
</body>
</html>
