let datatable = $(".m_datatable").mDatatable({
    data: {
        pageSize: 4,
        type: "remote",
        source: {
            read: {
                url: "/history",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $("#csrf-token").attr("content")
                },
                map: function(responses) {
                    data = [];
                    $.each(responses.data, function(index, response) {
                        data.push({
                            Slug: response.slug,
                            Date: response.date,
                            Film: response.film,
                            FilmUrl: response.film_url,
                            Diagnosis: response.diagnosis
                        });
                    });
                    return data;
                }
            }
        }
    },
    layout: {
        theme: "default",
        scroll: false,
        footer: false,
        spinner: {
            state: "brand",
            message: true,
            type: "loader",
            message: "Fetching records..."
        },
        icons: {
            asc: "la la-arrow-up",
            desc: "la la-arrow-down"
        }
    },
    sortable: true,
    filterable: true,
    pagination: true,
    search: {
        input: $("#search")
    },
    toolbar: {
        layout: ["info", "pagination"],
        placement: ["bottom"],
        items: {
            pagination: {
                pageSizeSelect: [4, 8],
                navigation: {
                    prev: true,
                    next: true,
                    first: false,
                    last: false
                }
            }
        }
    },
    translate: {
        records: {
            noRecords: "No records found"
        },
        toolbar: {
            pagination: {
                items: {
                    info: "Showing {{start}} - {{end}} of {{total}} diagnosis"
                }
            }
        }
    },
    columns: [
        {
            field: "Slug",
            title: "Record ID",
            width: 150,
            textAlign: "center",
            template: function(response) {
                return `<a href="#">${response.Slug.toUpperCase()}</a>`;
            }
        },
        {
            field: "Date",
            title: "Date",
            width: 150,
            textAlign: "center"
        },
        {
            field: "Film",
            title: "Film",
            width: 300,
            textAlign: "center",
            template: function(response) {
                return `${response.Film}<br><small><a href="${response.FilmUrl +
                    response.Film}" _target="blank">${
                    response.FilmUrl
                }</a></small>`;
            }
        },
        {
            field: "Diagnosis",
            title: "Diagnosis",
            width: 200,
            textAlign: "center",
            template: function(response) {
                if (response.Diagnosis.presence < 30) {
                    return `<span class="m-badge m-badge--wide badge-status--success">${response
                        .Diagnosis.pathology +
                        " " +
                        response.Diagnosis.presence}%</span>`;
                } else if (response.Diagnosis.presence < 60) {
                    return `<span class="m-badge m-badge--wide badge-status--info">${response
                        .Diagnosis.pathology +
                        " " +
                        response.Diagnosis.presence}%</span>`;
                } else {
                    return `<span class="m-badge m-badge--wide badge-status--danger">${response
                        .Diagnosis.pathology +
                        " " +
                        response.Diagnosis.presence}%</span>`;
                }
            }
        },
        {
            field: "Actions",
            title: "Actions",
            sortable: false,
            width: 100,
            textAlign: "center",
            template: function(response) {
                return `
                  <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" onclick="event.preventDefault(); delete_record('${
                      response.Slug
                  }');" data-skin="light" data-toggle="m-tooltip" data-placement="top" title="Delete">
                    <i class="la la-trash"></i>
                  </a>`;
            }
        }
    ]
});

// show delete modal
function delete_record(slug) {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        confirmButtonClass: "btn btn-focus m-btn m-btn--wide",
        cancelButtonClass: "btn btn-secondary m-btn m-btn--wide"
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: "/history/" + slug + "/delete",
                type: "POST",
                data: {
                    _token: $("#csrf-token").attr("content")
                },
                success: function(response) {
                    datatable.reload();
                    swal(
                        "Deleted!",
                        "Your record has been deleted.",
                        "success"
                    );
                }
            });
        }
    });
}
