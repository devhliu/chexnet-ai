<template>
  <div id="upload-image-modal" class="modal fade">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="m-portlet m-portlet--full-height">
          <div class="m-portlet__head" style="padding: 0 20px 0 20px; height: 4.0rem;">
            <div class="m-portlet__head-caption">
              <div class="m-portlet__head-title">
                <h4 class="m-portlet__head-text" style="font-size: 16px !important;">
                  Upload
                </h4>
              </div>
            </div>
            <div class="m-portlet__head-tools">
              <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item m-portlet__nav-item--last">
                  <a 
                    id="addNewImage"
                    href="#"
                    class="btn btn-sm btn-focus m-btn m-btn--bolder m-btn--air"
                    v-if="!added"
                  >
                    UPLOAD
                  </a>
                  <a 
                    href="#"
                    class="btn btn-sm btn-focus m-btn m-btn--bolder m-btn--air"
                    v-else-if="uploading"
                    v-on:click="reset"
                  >
                    CANCEL
                  </a>
                  <a 
                    href="#"
                    class="btn btn-sm btn-focus m-btn m-btn--bolder m-btn--air"
                    v-else-if="uploaded"
                    v-on:click="reset"
                  >
                    REMOVE
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="m-portlet__body" style="padding: 0px;">
            <vue-dropzone 
              id="dropzoneImage" 
              ref="dropzoneImage"
              :options="dropzoneOptions"
              @vdropzone-file-added="fileAddedEvent"
              @vdropzone-success="uploadSuccessEvent"
              @vdropzone-upload-progress="updateProgressEvent"
              @vdropzone-error="fileErrorEvent"
            >
            </vue-dropzone>
          </div>

          <div class="m-portlet__body"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";

export default {
    components: {
        vueDropzone: vue2Dropzone
    },

    data: () => ({
        added: false,
        uploading: false,
        uploaded: false,
        progress: 0,
        dropzoneOptions: {
            method: "POST",
            url: "/upload/image",
            maxFiles: 1,
            maxFilesize: 2,
            paramName: "image",
            filesizeBase: 1024,
            autoProcessQueue: true,
            chunking: true,
            retryChunks: true,
            retryChunksLimit: 2,
            headers: { "X-CSRF-Token": this.token },
            clickable: ["#addNewImage", "#dropzoneImage"],
            dictMaxFilesExceeded: "Maximum number of files has been exceeded!",
            dictInvalidFileType: "Unsupported file extension!",
            dictFileTooBig: "File size exceeds 2MB!",
            acceptedFiles: "image/*",
            previewTemplate: `
              <div class="text-center m--font-focus m--padding-left-100 m--padding-right-100">
                <i class="flaticon-multimedia-1" style="font-weight: 600; font-size: 65px;"></i>
                <br>
                <span id="fileStatus" style="font-size: 20px;">
                  Upload in Progress
                </span> 
                <br>
                <span id="fileProgress" class="note needsclick" style="font-weight: 600; font-size: 14px;">
                  0%
                </span>
                <br><br>
                <div class="progress">
                  <div id="fileProgressBar" class="progress-bar progress-bar-animated progress-bar-striped m--bg-focus"></div>
                </div>
              </div>`,
            dictDefaultMessage: `
              <i class="flaticon-multimedia-1" style="font-weight: 500; font-size: 65px;"></i>
              <br>
              <span style="font-size: 20px;">
                Drop an Image or Click to Upload
              </span>
              <br>
              <span class="note needsclick" style="font-size: 14px;">
                Drag and drop anywhere you want and start uploading your profile image now.
              </span>`
        }
    }),

    methods: {
        fileAddedEvent(file) {
            this.added = true;
            this.uploading = true;
            this.uploaded = false;
        },
        fileErrorEvent(file, message, xhr) {
            this.sweetAlert(message, "error");
            this.notify(message, "error");
            this.reset();
            this.$refs.dropzoneImage.removeAllFiles(true);
        },
        uploadSuccessEvent(file, response) {
            $(".img-link").each(function() {
                $(this).attr("src", response.url);
            });
            this.$refs.dropzoneImage.removeAllFiles(true);
            this.notify("Profile image updated.", "success");
            this.uploading = false;
            this.uploaded = true;
            $("#upload-image-modal").modal("toggle");
        },
        updateProgressEvent(file, progress, bytesSent) {
            this.progress = Math.round((100 * bytesSent) / file.size);
            $("#fileStatus").html("Upload in Progress");
            $("#fileProgress").html(this.progress + "%");
            $("#fileProgressBar").attr("style", "width:" + this.progress + "%");
        },
        reset() {
            this.added = false;
            this.uploaded = false;
            this.uploading = false;
        },
        sweetAlert(message, type) {
            swal({
                type: type,
                title: "Ooops",
                text: message,
                confirmButtonClass:
                    "btn btn-focus m-btn m-btn--wide m-btn--air",
                animation: true
            });
        },
        notify(message, type) {
            if (type == "success") {
                toastr.success(message);
            } else if (type == "error") {
                toastr.error(message);
            }
        }
    }
};
</script>

<style>
#dropzoneImage {
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
    padding: 80px;
    text-align: center;
    cursor: pointer;
}

#dropzoneImage .dz-message {
    margin: 0 0 5px 0;
    padding: 0;
    font-weight: 500;
    font-size: 1.1rem;
}
#dropzoneImage .note {
    font-size: 0.85rem;
    font-weight: 500;
}
#dropzoneImage .dz-preview .dz-image {
    border-radius: 20px;
    overflow: hidden;
    width: 120px;
    height: 120px;
    position: relative;
    display: block;
    z-index: 10;
}
#dropzoneImage {
    border: none;
}
#dropzoneImage .dz-message,
#dropzoneImage .note {
    color: #a26ff9;
}
#dropzoneImage:hover,
#dropzoneImage .dz-drag-hover .dz-message {
    opacity: 0.7;
}
#dropzoneImage .dz-preview .dz-error-message {
    margin-top: 20px;
}
.dropzoneImage .dz-preview .dz-remove {
    margin-top: 5px;
}
</style>
