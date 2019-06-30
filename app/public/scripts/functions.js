/**
 * Executes a response from an ajax-request
 * Ajax-responses are always JSON-encoded and are actually an array
 * The first element is the demanded action on the client side
 * Whereas action € {update, redirect}
 * The second element is the id of the element where the action is performed
 * The third element is the actual content of the response
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 * @todo think about security and about other actions to implement
 * @todo should there be an else-clause if an invalid action was transmitted?
 * @return void
 */
 
execResponse = function(response)
{
    var json = response.responseJSON;
    var action = json.shift();
    var id = json.shift();
    var content = unescape(json.shift());
    if(action == 'update') $(id).update(content);
    if(action == 'redirect') document.location = content;
};

prmt = function(a, m)
{
    var x = window.confirm("Sind sie sicher? Löschen ist irreversibel!");
    if(x) {
      new Ajax.Request(a, {method: m, onSuccess: function(response){execResponse(response);}, onFailure: function(){ alert('ERROR IN SCRIPT.');}});
    }
};
