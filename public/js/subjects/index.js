/**
 * An object for managing tasks related to subjects
 */
function Subject(url, resourceName) {
   Resource.call(this, url, resourceName);
}

App.extend(Resource, Subject);

Subject.prototype.prepareForUpdate = function (resource) {
   $("input[name=name]").val(resource.name);
   $("input[name=subject_code]").val(resource.subject_code);
   $("#professors-select").val(resource.professor_ids).change();
   $('select[name=lab]').val(resource.lab);
};

Subject.prototype.clearFormFields = function () {
   if (!$("#resource-modal").hasClass("editing")) {
      $("input[name=name]").val("");
      $("input[name=subject_code]").val("");
      $("#professors-select").val("").trigger("change");
      $('select[name=lab]').val("");
   }
};

window.addEventListener("load", function () {
   var subject = new Subject("subjects", "Subject");
   subject.init();

   // Assuming this code is triggered when the user submits the form to add a new subject
   $("#add-subject-form").submit(function (event) {
      event.preventDefault();

      // Collect form data
      var formData = $(this).serialize();

      // Make AJAX request to add new subject
      $.ajax({
         url: "/subjects",
         method: "POST",
         data: formData,
         success: function (response) {
            // Remove the editing class from the modal
            $("#resource-modal").removeClass("editing");

            // Clear the form fields
            subject.clearFormFields();

            // Any other success handling code
         },
         error: function (xhr, status, error) {
            // Handle errors
         },
      });
   });

   $("#resource-modal").on("hidden.bs.modal", function () {
      subject.clearFormFields();
   });

   $("#resource-modal").on("show.bs.modal", function () {
      $("#resource-modal").removeClass("editing");
   });
});