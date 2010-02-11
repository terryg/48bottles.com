// JavaScript Methods for Recipe Press
function recipeSearch() {
    var recipe = urlencode(document.recipe_form.recipe_search.value);
    var slug = urlencode(document.recipe_form.slug_search.value);
    var user = document.recipe_form.user_search.options[document.recipe_form.user_search.selectedIndex].value;
    var category = document.recipe_form.category_search.options[document.recipe_form.category_search.selectedIndex].value;
    var status = document.recipe_form.status_search.options[document.recipe_form.status_search.selectedIndex].value;
	
    var url = document.recipe_form.method;
        
    if (recipe) {
        url+= '&recipe=' + recipe;
    }

    if (slug) {
        url+= '&slug=' + slug;
    }

    if (user) {
        url+= '&user=' + user;
    }

    if (category) {
        url+= '&category=' + category;
    }

    if (status) {
        url+= '&status=' + status;
    }
	
    window.location.href = url;
}

function commentSearch() {
    var author = urlencode(document.recipe_form.author_search.value);
    var comment = urlencode(document.recipe_form.comment_search.value);
    var recipe = document.recipe_form.recipe_search.options[document.recipe_form.recipe_search.selectedIndex].value;

    var url = document.recipe_form.method;

    if (author) {
        url+= '&author=' + author;
    }

    if (comment) {
        url+= '&comment=' + comment ;
    }

    if (recipe) {
        url+= '&recipe=' + recipe;
    }

    window.location.href = url;
}

function ingredientSearch() {
    var name = urlencode(document.ingredient_form.name_search.value);
    var slug = urlencode(document.ingredient_form.slug_search.value);
    var page = document.ingredient_form.page_search.options[document.ingredient_form.page_search.selectedIndex].value;
    var urls = urlencode(document.ingredient_form.url_search.value);
    var status = document.ingredient_form.status_search.options[document.ingredient_form.status_search.selectedIndex].value;

    var url = document.ingredient_form.method;

    if (name) {
        url+= '&name=' + name;
    }

    if (slug) {
        url+= '&slug=' + slug;
    }
    
    if (page) {
        url+= '&page_link=' + page;
    }
    
    if (urls) {
        url+= '&url=' + urls;
    }

    if (status) {
        url+= '&status=' + status;
    }
        

    window.location.href = url;
}

function onClickRPComment(id, action) {
    if (action == 'spam') {
        if (!confirm('Are you sure you want to mark this comment as spam?')) {
            return false;
        }
    }

    switch (action) {
        case 'unapprove':
            status = 'pending';
            break;
        case 'approve':
            status = 'active';
            break;
        case 'spam':
            status = 'spam';
            break;
        default:
            status = action;
            break;
    }

    jQuery.post(ajaxurl + '?action=recipe_press_comment_action', {
        id: id,
        action: action,
        status: status
    },
    function (msg) {
        jQuery('#rp_comment_status_' + id).html(msg);

        switch (msg) {
            case 'Active':
                jQuery('#rp_comment_' + id).addClass('approved').removeClass('unapproved').removeClass('spam');
                jQuery('#rp_comment_approve_' + id).css('display', 'none');
                jQuery('#rp_comment_unapprove_' + id).css('display', '');
                jQuery('#rp_comment_spam_' + id).css('display', '');
                break;
            case 'Pending':
                jQuery('#rp_comment_' + id).addClass('unapproved').removeClass('approved').removeClass('spam');
                jQuery('#rp_comment_approve_' + id).css('display', '');
                jQuery('#rp_comment_unapprove_' + id).css('display', 'none');
                jQuery('#rp_comment_spam_' + id).css('display', '');
                break;
            case 'Spam':
                jQuery('#rp_comment_' + id).addClass('spam').removeClass('approved').removeClass('unapproved');
                jQuery('#rp_comment_approve_' + id).css('display', '');
                jQuery('#rp_comment_unapprove_' + id).css('display', 'none');
                jQuery('#rp_comment_spam_' + id).css('display', 'none');
                break;
        }
    });

    return false;
}

function urlencode(str) {
    return escape(str).replace(/\+/g,'%2B').replace(/%20/g, '+').replace(/\*/g, '%2A').replace(/\//g, '%2F').replace(/@/g, '%40');
}	

/* Set up the Autocompleter field */
try {
    jQuery(document).ready(function(){
        /* Recipe AJAX Stuff */
        jQuery("input#recipe_search").autocomplete(ajaxurl +"?action=recipe_press_recipe_title" );
        jQuery("input#recipe_search").result(function(event, data, formatted){
            var url = document.recipe_form.method + "&action=edit&id=" + data[1];
            window.location.href = url;
            return false;
        });

        jQuery("input#slug_search").autocomplete(ajaxurl +"?action=recipe_press_recipe_slug" );
        jQuery("input#slug_search").result(function(event, data, formatted){
            var url = document.recipe_form.method + "&action=edit&id=" + data[1];
            window.location.href = url;
            return false;
        });

        jQuery("#rp_ingredients tbody").sortable({
            helper: fixHelper,
            cursor: 'crosshair',
            items: 'tr',
            axis: 'y',
            distance:  15
        });
    });

} catch(error) {}

var fixHelper = function(e, ui) {
    ui.children().each(function() {
        jQuery(this).width(jQuery(this).width());
    });
    return ui;
};