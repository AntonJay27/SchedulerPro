/**
 * An object for managing professors module
 */
function Professor(url, resourceName) {
   Resource.call(this, url, resourceName);
}

App.extend(Resource, Professor);

Professor.prototype.prepareForUpdate = function (resource) {
   console.log(resource);
   $("input[name=name]").val(resource.name);
   $("#subjects-select").val(resource.subject_ids).change();
   $("#timeslots-select").val(resource.timeslots).change();
};

Professor.prototype.clearFormFields = function () {
   if (!$("#resource-modal").hasClass("editing")) {
      $("input[name=name]").val("");
      $("#subjects-select").val("").trigger("change");
      $("#timeslots-select").val("").trigger("change");
   }
};

window.addEventListener("load", function () {
   var professor = new Professor("professors", "Professor");
   professor.init();

   // Assuming this code is triggered when the user submits the form to add a new professor
   $("#add-professor-form").submit(function (event) {
      event.preventDefault();

      // Collect form data
      var formData = $(this).serialize();

      // Make AJAX request to add new professor
      $.ajax({
         url: "/professors",
         method: "POST",
         data: formData,
         success: function (response) {
            // Remove the editing class from the modal
            $("#resource-modal").removeClass("editing");

            // Clear the form fields
            professor.clearFormFields();

            // Any other success handling code
         },
         error: function (xhr, status, error) {
            // Handle errors
         },
      });
   });

   $("#resource-modal").on("hidden.bs.modal", function () {
      professor.clearFormFields();
   });

   $("#resource-modal").on("show.bs.modal", function () {
      $("#resource-modal").removeClass("editing");
   });
});