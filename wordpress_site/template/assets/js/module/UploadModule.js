export default function UploadModule() {
  $(document).ready(function () {
    $('#upfile').on('change', function () {
      const fileName = this.files[0]?.name;

      if (fileName) {
        $(this)
          .closest('.form-box')
          .find('.upload-txt')
          .text(fileName);
      }
    });
  });
}