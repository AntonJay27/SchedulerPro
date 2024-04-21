/**
 * An object for managing tasks related to rooms
 */
function Room(url, resourceName) {
    Resource.call(this, url, resourceName);
}

App.extend(Resource, Room);

Room.prototype.prepareForUpdate =  function(resource) {
    $('input[name=name]').val(resource.name);
    $('select[name=lab]').val(resource.lab);
};

window.addEventListener('load', function(){
    var room = new Room(`rooms`, 'Room');
    room.init();
});