jQuery(document).ready(function ($) {
    $("#ecsa-search").on("keyup", function (e) {
        if (13 == e.which) {
            e.preventDefault();
            var selectables = $(".typeahead").siblings(".tt-menu").find(".tt-selectable a");
            selectables.length > 0 && (window.location = $(selectables[0]).attr("href")), $(".typeahead").typeahead("close")
        }
    }), 
     
    $(".ecsa-search-icon").on("click", function (e) {
        e.preventDefault();
        var selectables = $(".typeahead").siblings(".tt-menu").find(".tt-selectable a");
        selectables.length > 0 && (window.location = $(selectables[0]).attr("href")), $(".typeahead").typeahead("close")
    }),
     
    $(".ecsa-search").each(function () {
        var thisEle = $(this),
        DisablePast = thisEle.data("disable-past");
        noUpResult = thisEle.data("no-up-result"), noPastResult = thisEle.data("no-past-result"), ShowEvents = thisEle.data("show-events"), UpcomingHeading = thisEle.data("up-ev-heading"), PastHeading = thisEle.data("past-ev-heading");
        
        //upcoming Events
        var upcoming_source = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace("name"),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                identify: function(obj) { return obj.name; },
                prefetch: {
                    url: ecsaSearch.prefetchUpcomingUrl,
                    cache: false
                    }
            });
        upcoming_source.initialize();
        function futureEventSource(q, sync) {
            events = upcoming_source.index.datums;
            if( Object.keys( events )[0] == 'undefined'){
                return;
            }
            if (q === '') {
            sync(upcoming_source.all());
            }
            else {
                upcoming_source.search(q, sync);
            }
        }
        
           
    // past Events data
    var past_source = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        identify: function(obj) { return obj.name; },
       // local: search_data
       prefetch: {
        url: ecsaSearch.prefetchPastUrl,
        cache: false
       }
    });
  /*  var defaultd=[];
   $.each( past_source, function( key, value ) {
        defaultd.push(value.name);
    });
    */

   past_source.initialize();
    
    function PastEventSource(q, sync) {
        var event = past_source.index.datums;
        if(DisablePast==true || Object.keys(event)[0] == 'undefined'){
            return;
        }
        if (q === '') {            
        sync(past_source.all());
        }
       else {
        past_source.search(q, sync);
        }
    }

    var past_events_empty = ['<div class="empty-message">', noPastResult, "</div>"].join("\n");
    if( DisablePast == true ){
        past_events_empty = '';
    }
   
        thisEle.find(".typeahead").typeahead({
            minLength: 0,
            highlight: !0
        }, {
            name: "matched-links",
            displayKey: "name",
            limit: ShowEvents,
            source: futureEventSource,
            async: true,
            templates: {
                header: '<h3 class="ecsa-heading">' + UpcomingHeading + "</h3>",
                empty: ['<div class="empty-message">', noUpResult, "</div>"].join("\n"),
                suggestion: Handlebars.compile(document.getElementById("ecsa-search_temp").innerHTML)
            }
        }, {
            name: "matched-links",
            displayKey: "name",
            limit: ShowEvents,
            source: PastEventSource,
            async: true,
            templates: {
                header: '<h3 class="ecsa-heading">' + PastHeading + "</h3>",
                empty: past_events_empty,
                suggestion: Handlebars.compile(document.getElementById("ecsa-search_temp").innerHTML)
            }
        
        })


    })
});