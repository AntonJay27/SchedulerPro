/**
 * An object for managing tasks related to subjects
 */
function CollegeClass(url, resourceName) {
   Resource.call(this, url, resourceName);
}

App.extend(Resource, CollegeClass);

CollegeClass.prototype.init = function () {
   var self = this;

   Resource.prototype.init.call(self);

   $(document).on("click", "#subject-add", function () {
      self.addSubject();
   });

   $(document).on("click", ".subject-remove", function (event) {
      var $el = $(event.target);
      var id = $el.data("id");

      $("#subject-" + id + "-container").remove();
   });

   self.addSubject();
};

CollegeClass.prototype.addSubject = function (data) {
   var template = $("#subject-template").html();
   var id = new Date().valueOf();
   data = data || null;

   template = template.replace(/{ID}/g, id);

   if (data) {
      $("#subjects-container").prepend(template);
      $("[name=subject-" + id + "]")
         .val(data.subject_id)
         .change();
      $("[name=subject-" + id + "-units]").val(data.units);
      $("[name=period-" + id + "]")
         .val(data.academic_period_id)
         .change();
   } else {
      $("#subjects-container").append(template);
   }

   $("[name=subject-" + id + "]").select2();
   $("[name=period-" + id + "]").select2();
};

CollegeClass.prototype.prepareForUpdate = function (resource) {
   var self = this;

   $("input[name=name]").val(resource.name);

   // Populate academic period select element with the correct value
   var academicPeriodId =
      resource.subjects.length > 0
         ? resource.subjects[0].pivot.academic_period_id
         : null;
   $("#academic-period-select").val(academicPeriodId).trigger("change");

   // Remove existing subjects
   $("#subjects-container .subject-form").remove();

   // Iterate over subjects and populate the form
   $.each(resource.subjects, function (index) {
      var subject = this;
      var data = {
         subject_id: subject.id,
         academic_period_id: subject.pivot.academic_period_id,
         units: subject.pivot.units,
      };
      self.addSubject(data);
   });

   self.addSubject();
};

CollegeClass.prototype.clearForm = function () {
   Resource.prototype.clearForm.call(this);

   // Clear input fields
   $("input[name=name]").val("");
   $("#academic-period-select").val("").trigger("change");

   // Remove existing subjects
   $("#subjects-container .subject-form").remove();

   // Add one empty subject field
   this.addSubject();
};

CollegeClass.prototype.submitResourceForm = function () {
   var $form = $("#resource-form");
   var url = $form.attr("action");
   var form;

   var data = {
      _token: this.csrfToken,
      _method: $("[name=_method]").val(),
      name: $form.find("[name=name]").val(),
   };

   var academicPeriodId = $form.find("#academic-period-select").val(); // Get selected academic period

   var subjects = {};

   $(".subject-form").each(function (index) {
      var $container = $(this);
      var subjectId = $container.find(".subject-select").val();
      var units = $container.find(".subject-units").val();

      if (subjectId && units) {
         subjects[subjectId] = {
            units: units,
            academic_period_id: academicPeriodId, // Apply selected academic period to all subjects
         };
      }
   });

   data.subjects = subjects;
   var formData = new FormData();
   App.appendFormdata(formData, data);

   form = {
      url: url,
      data: formData,
   };

   App.submitForm(form, this.refreshPage, $("#errors-container"), true, true);
};

window.addEventListener("load", function () {
   var collegeClass = new CollegeClass("classes", "Class");
   collegeClass.init();
});
