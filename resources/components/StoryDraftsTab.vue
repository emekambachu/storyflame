<template>
    <div class="px-4 w-full mt-2 flex flex-col gap-8">
        <div class="p-1 bg-zinc-200 rounded-lg w-full">
            <div
                class="flex flex-col items-center gap-2 rounded-lg w-full p-4 border border-dashed border-gray-400"
                @click="triggerFileUpload"
            >
                <div
                    class="rounded-full w-10 h-10 shrink-0 flex items-center justify-center bg-gray-400"
                >
                    <plus-icon class="text-white" />
                </div>
                <span class="text-sm text-gray-400 font-normal">
                    Upload new
                </span>
            </div>
            <input
                ref="fileInput"
                type="file"
                class="hidden"
                @change="handleFileUpload"
                accept=".pdf"
            />
        </div>

        <div class="flex flex-col gap-6">
            <h6 class="text-base text-black font-bold">
                All Drafts
                <span class="text-slate-500">
                    ({{ files.length }}
                    {{ files.length === 1 ? 'file' : 'files' }})
                </span>
            </h6>

            <div
                v-for="file in files"
                :key="file.name"
                class="flex items-start gap-2"
            >
                <file-icon class="text-slate-500" />
                <div class="flex max-w-full flex-col gap-1">
                    <p class="text-slate-500 text-sm font-semibold break-words">
                        {{ file.name }}
                    </p>
                    <div class="w-full flex items-center gap-2">
                        <span class="text-slate-500 text-xs font-normal">
                            {{ (file.size / 1048576).toFixed(2) }} MB
                        </span>
                        <point-icon class="text-slate-500" />
                        <span class="text-slate-500 text-xs font-normal">
                            {{ formatDate(new Date()) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import FileIcon from '@/components/icons/FileIcon.vue'
import PlusIcon from '@/components/icons/PlusIcon.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

const fileInput = ref(null)
const files = ref([])

const triggerFileUpload = () => {
    fileInput.value.click()
}

const handleFileUpload = (event) => {
    const selectedFiles = event.target.files
    const maxSizeMB = 50
    const allowedFormats = [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/msword',
        'application/x-iwork-pages-sffpages',
    ]

    for (let i = 0; i < selectedFiles.length; i++) {
        const file = selectedFiles[i]
        if (file.size / 1048576 > maxSizeMB) {
            alert(
                `File ${file.name} exceeds the size limit of ${maxSizeMB} MB.`
            )
        } else if (!allowedFormats.includes(file.type)) {
            alert(`File ${file.name} is not a supported format.`)
        } else {
            files.value.push(file)
        }
    }
}

const formatDate = (date) => {
    const options = {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
    }
    const formattedDate = new Intl.DateTimeFormat('en-US', options).format(date)
    const datePart = formattedDate.split(', ')[0]
    let timePart = new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
    }).format(date)
    timePart = timePart.replace(' AM', 'a').replace(' PM', 'p').replace(' ', '')
    return `${datePart} at ${timePart}`
}
</script>

<style scoped></style>
