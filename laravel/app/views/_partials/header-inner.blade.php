@if(Auth::check())
    <header id="Bannerblock2">
        <div class="container text-center">
            <div class="row-fluid">
                <div class="span4 Header-title01">
                    <p>&nbsp;<br>
                        <span>&nbsp;</span></p>
                </div>
                <div class="span3 pull-right">
                    <div class="Header-Account-info">
                        <span> Welcome {{{ Auth::user()->username }}} </span>
                        |
                        {{ HTML::link(route('logout'), trans('Sign Out'), ['class' => 'signin', 'title' => trans('Sign Out')]) }}
                    </div>
                    <form method="post" action="/user/search" id="searchuser">
                        <div class="Header-search">
                            <input name="searchvalue" id="searchvalue" type="text" style="line-height: 20px" />
                            {{ HTML::image('/img/search-img.jpg', trans('Search'), ['class' => 'submit', 'id' => 'search']) }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
@else
    <header id="Bannerblock2">
        <div class="container text-center">
            <div class="row-fluid">
                <div class="span4 Header-title01">
                    <p>Join<br>
                        <span>Botangle</span></p>
                </div>
                <form method="post" action="/user/search" id="searchuser">
                    <div class="span3 pull-right">
                        <div class="Header-search">
                            <input name="searchvalue" id="searchvalue" type="text" style="line-height: 20px" />
                            {{ HTML::image('/img/search-img.jpg', trans('Search'), ['class' => 'submit', 'id' => 'search']) }}
                        </div>
                        <div class="Header-Free-info">{{ trans('Find help immediately!') }}<br>
                            <!--          <span>Try for 7 days free!</span> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </header>
@endif

<script>
    var $j = jQuery.noConflict();

    jQuery(document).ready(function(){
        jQuery("#search").click(function(){
            jQuery("#searchuser").submit();
        })
    });
</script>
{{--
echo $this->Html->script(array('/croogo/js/autocomplete/jquery-1.9.1',
        '/croogo/js/autocomplete/jquery.ui.core', '/croogo/js/autocomplete/jquery.ui.widget', '/croogo/js/autocomplete/jquery.ui.position', '/croogo/js/autocomplete/jquery.ui.menu', '/croogo/js/autocomplete/jquery.ui.autocomplete',
    ));

echo $this->Html->css(array(
        '/croogo/css/autocomplete/themes/base/jquery.ui.all', '/croogo/css/autocomplete/demos',
    ));
<script src="/Croogo/js/autocomplete/jquery.min.js"></script>
<script src="/Croogo/js/autocomplete/bootstrap.min.js"></script>
--}}
<script>
    var suburl = "/subject/search";

    var $j = jQuery.noConflict();
    datasubject = "";
    jQuery(function() {
        jQuery.getJSON(suburl, function(response) {
            datasubject = response;
            $j("#searchvalue,#LessonSubject").autocomplete({
                minLength: 0,
                source: datasubject,
                focus: function(event, ui) {

                    $j("#searchvalue,#LessonSubject").val(ui.item.label);
                    return false;
                },
                select: function(event, ui) {

                    $j("#searchvalue,#LessonSubject").val(ui.item.label);
                    return false;
                }
            })
                .data("ui-autocomplete")._renderItem = function(ul, item) {
                return $j("<li>")
                    .append("<a>" + item.label + "</a>")
                    .appendTo(ul);
            };
            if (document.URL.indexOf('createlesson') >= 0) {
                $j("#LessonSubject").autocomplete({
                    minLength: 0,
                    source: data,
                    focus: function(event, ui) {
                        $j("#LessonSubject").val(ui.item.label);
                        return false;
                    },
                    select: function(event, ui) {
                        $j("#LessonSubject").val(ui.item.label);
                        return false;
                    }
                })
                    .data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $j("<li>")
                        .append("<a>" + item.label + "</a>")
                        .appendTo(ul);
                };
            }
        })

    });

</script>