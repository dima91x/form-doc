<template>
    <div class="container">
        <form class="w-50 needs-validation" novalidate>
            <div class="mb-3">
                <label for="inputDocName" class="form-label">Название документа</label>
                <input type="text" class="form-control" v-model="docName" id="docName" required>
            </div>
            <div class="mb-3">
                <label for="inputCreateDate" class="form-label">Дата создания</label>
                <input type="date" class="form-control" v-model="createDate" id="createDate">
            </div>
            <button @click="generateDoc" type="submit" class="btn btn-primary">Сгенировать</button>
        </form>
    </div>
</template>

<script>
export default {
    name: "app",

    data() {
        return {
            docName: null,
            createDate: null
        }
    },

    methods: {
        generateDoc(e) {
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })

            if (this.docName) {
                axios({
                    url: '/api/doc/generate',
                    method: 'POST',
                    data: {docName: this.docName, createDate: this.createDate},
                }).then((response) => {
                    this.downloadFile(response.data.file, response.data.fileName, response.data.fileType);
                });
            }
        },

        base64ToBlob(urlData, type) {
            let arr = urlData.split(',');
            let array = arr[0].match(/:(.*?);/);
            let mime = (array && array.length > 1 ? array[1] : type) || type;

            let bytes = window.atob(arr[1]);

            let ab = new ArrayBuffer(bytes.length);

            let ia = new Uint8Array(ab);
            for (let i = 0; i < bytes.length; i++) {
                ia[i] = bytes.charCodeAt(i);
            }
            return new Blob([ab], {
                type: mime
            });
        },
        downloadExportFile(blob, fileName, fileType) {
            let downloadElement = document.createElement('a');
            let href = blob;
            if (typeof blob == 'string') {
                downloadElement.target = '_blank';
            } else {
                href = window.URL.createObjectURL(blob);
            }
            downloadElement.href = href;
            downloadElement.download = fileName + '.' + fileType;
            document.body.appendChild(downloadElement);
            downloadElement.click();
            document.body.removeChild(downloadElement);
            if (typeof blob != 'string') {
                window.URL.revokeObjectURL(href);
            }
        },

        downloadFile(base64, fileName, fileType) {
            let typeheader = 'data: application/' + fileType + '; base64,'
            let convertBase64 = typeheader + base64;
            let blob = this.base64ToBlob(convertBase64, fileType)
            this.downloadExportFile(blob, fileName, fileType)
        }
    }
}
</script>

<style scoped>

</style>
