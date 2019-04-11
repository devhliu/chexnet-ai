<template>
  <div class="m-portlet">
    <div
      class="m-portlet__head"
      style="padding: 0 20px 0 20px; height: 4.0rem;"
    >
      <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
          <h4 
            class="m-portlet__head-text"
            style="font-size: 16px !important;"
          >
            Detector
          </h4>
        </div>
      </div>

      <div class="m-portlet__head-tools">
        <ul class="m-portlet__nav">
          <li class="m-portlet__nav-item m-portlet__nav-item--last">
            <a
              id="addNewXray"
              href="#"
              class="btn btn-sm btn-focus m-btn m-btn--bolder m-btn--air"
              v-if="!added"
            >
              ADD X-RAY
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

    <div class="m-portlet__body" style="padding: 0rem 1.25rem;">
      <vue-dropzone 
        id="dropzoneXray" 
        ref="dropzoneXray"
        :options="dropzoneOptions"
        @vdropzone-file-added="fileAddedEvent"
        @vdropzone-success="uploadSuccessEvent"
        @vdropzone-upload-progress="updateProgressEvent"
        @vdropzone-error="fileErrorEvent"
        v-if="!analyzed"
      ></vue-dropzone>

      <div v-else>
        <div class="row" style="background: rgb(249, 250, 250);">
          <div class="col-4 offset-4">
            <div class="card">
                <progressive-img
                  class="card-img"
                  :src="fileURL"
                  :title="filename"
                  style="cursor:pointer;"
                />
            </div>
          </div>
        </div>

        <histogram-chart
          :chartdata="chartdata"
          :chartoptions="chartoptions"
        ></histogram-chart>
      </div>
		</div>

    <div 
      class="m-portlet__body"
      style="
        border-top: 1px dashed #ebedf2;
        box-shadow: 0 1px 0 rgba(0,0,0,0.05) inset, 0 2px 0 rgba(0,0,0,0.1);
        padding: 0px; 
        text-align: center;">
      <div class="alert m-alert--default" style="margin-bottom:0; background-color: #f9fafa;">
        <div class="m-alert__text">
          CheXNet: Radiologist-Level Pneumonia Detection on Chest X-Rays with Deep Learning. <br>
          Deep Learning Model Graciously Adapted from <a href="https://arxiv.org/pdf/1711.05225.pdf" class="m-link m-link--secondary" target="_blank">CheXNet</a> Paper.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import HorizontalBar from "./Chart.vue";
import vue2Dropzone from "vue2-dropzone";

export default {
    components: {
        vueDropzone: vue2Dropzone,
        histogramChart: HorizontalBar
    },
    data: () => ({
        token: document.getElementById("csrf-token").getAttribute("content"),
        added: false,
        uploading: false,
        uploaded: false,
        analyzed: false,
        progress: 0,
        fileURL: null,
        filename: null,

        dropzoneOptions: {
            method: "POST",
            url: "/upload/xray",
            maxFiles: 1,
            maxFilesize: 15,
            paramName: "image",
            filesizeBase: 1024,
            autoProcessQueue: true,
            chunking: true,
            retryChunks: true,
            retryChunksLimit: 2,
            clickable: ["#addNewXray", "#dropzoneXray"],
            dictMaxFilesExceeded: "Maximum number of files has been exceeded!",
            dictInvalidFileType: "Unsupported file extension!",
            dictFileTooBig: "File size exceeds 15MB!",
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
              <i class="flaticon-multimedia-1" style="font-weight: 600; font-size: 65px;"></i>
              <br>
              <span style="font-size: 20px;">
                Drop an X-ray Image or Click to Upload
              </span>
              <br>
              <span class="note needsclick" style="font-size: 14px;">
                Drag and drop anywhere you want and start uploading your X-ray image now.
              </span>`
        },

        chartdata: {
            labels: [],
            datasets: [
                {
                    label: "Presence",
                    backgroundColor: "#9816f4",
                    data: []
                },
                {
                    label: "Absence",
                    backgroundColor: "#f3f3fb",
                    data: []
                }
            ]
        },
        chartoptions: {
            title: {
                display: true,
                position: "top",
                text: "Pathological Predictive Results",
                padding: 20
            },
            tooltips: {
                intersect: true,
                mode: "nearest",
                xPadding: 30,
                yPadding: 30,
                caretPadding: 0,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label =
                            " " +
                                data.datasets[tooltipItem.datasetIndex].label ||
                            " ";

                        if (label) {
                            label += ": ";
                        }

                        return label + tooltipItem.xLabel + "%";
                    }
                }
            },
            legend: {
                display: true,
                position: "right"
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        display: true,
                        gridLines: {
                            display: true
                        },
                        stacked: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return value + "%";
                            }
                        }
                    }
                ],
                yAxes: [
                    {
                        barPercentage: 0.7,
                        display: true,
                        gridLines: {
                            display: true
                        },
                        stacked: true
                    }
                ]
            },
            layout: {
                padding: {
                    left: 30,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            }
        }
    }),

    methods: {
        fileAddedEvent(file) {
            this.added = true;
            this.uploading = true;
            this.analyzed = false;
        },
        fileErrorEvent(file, message, xhr) {
            this.sweetAlert(message, "error");
            this.notify(message, "error");
            this.reset();
            this.$refs.dropzoneXray.removeAllFiles(true);
        },
        uploadSuccessEvent(file, response) {
            this.uploading = false;
            this.uploaded = true;
            $("#fileStatus").html("Running ChexNet");
            this.$http
                .post("/analyze/" + file.name, {
                    _token: this.token
                })
                .then(response => {
                    this.chartdata.labels = [];
                    this.chartdata.datasets[0].data = [];
                    this.chartdata.datasets[1].data = [];

                    for (let [pathology, diagnosis] of Object.entries(
                        response.body.pathologies
                    )) {
                        this.chartdata.labels.push(pathology);
                        this.chartdata.datasets[0].data.push(
                            diagnosis.presence
                        );
                        this.chartdata.datasets[1].data.push(diagnosis.absence);
                    }
                    this.filename = response.body.film;
                    this.fileURL = response.body.film_url;
                    this.analyzed = true;
                })
                .catch(error => {
                    console.error(error);
                });
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
            this.analyzed = false;
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
#dropzoneXray {
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
    padding: 100px;
    background: transparent;
    text-align: center;
    cursor: pointer;
}

#dropzoneXray .dz-message {
    margin: 0 0 5px 0;
    padding: 0;
    font-weight: 500;
    font-size: 1.1rem;
}
#dropzoneXray .note {
    font-size: 0.85rem;
    font-weight: 500;
}
#dropzoneXray .dz-preview .dz-image {
    border-radius: 20px;
    overflow: hidden;
    width: 120px;
    height: 120px;
    position: relative;
    display: block;
    z-index: 10;
}
#dropzoneXray {
    border: none;
}
#dropzoneXray .dz-message,
#dropzoneXray .note {
    color: #a26ff9;
}
#dropzoneXray:hover {
    transition: opacity 0.2s;
    -webkit-transition: opacity 0.2s;
    opacity: 0.6;
}
#dropzoneXray .dz-preview .dz-error-message {
    margin-top: 20px;
}
.dropzoneVideo .dz-preview .dz-remove {
    margin-top: 5px;
}
</style>
