<link type="text/css" href="/jquery/v1.10.2/20130324/jquery-ui-1.10.2.custom/css/overcast/jquery-ui-1.8.19.custom.css" rel="Stylesheet" />
<link rel="stylesheet" href="css/style.css" type="text/css" /> 
<link rel="icon" type="image/png" href="/images/favicon.png" />
<script type="text/javascript" src="/js/common_functions.js"></script>
<script type="text/javascript" src="/jquery/v1.10.2/20130324/jquery-ui-1.10.2.custom/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/jquery/v1.10.2/20130324/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom.min.js"></script>
<!--<script src="/js/cufon-yui.js" type="text/javascript"></script>
<script src="/js/BabelSans_500.font.js" type="text/javascript"></script>
<script src="/js/jquery.easing.1.3.js" type="text/javascript"></script>-->
<script type="text/javascript" >
	
	$(document).ready(function(){
        
        $("#score_navigator").click(
            function() {
        
                var selected = $(this).data('selected');
                if (!selected) {
                    $(this).parent().find("ul.subnav").slideDown('fast').show();
                }
                else {
                    $(this).parent().find("ul.subnav").slideUp('slow');
                }
                $(this).data("selected", !selected);
            }

        );
        
    });

</script>

<meta name="description" content="LetsPlayGlobalGames is a fun and interactive game for learning the countries, capitals and flags of the world.  Click Play or Learn to begin help the world become more self-aware!" />
<meta name="keywords" content="game, learn, countries, capitals, flags, fun, geography, education, globe, world, aprender mundial, globo, divertido" />
<meta name="author" content="John Fisher" />
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<!--<meta id="meta_revised_date" name="revised"/>-->