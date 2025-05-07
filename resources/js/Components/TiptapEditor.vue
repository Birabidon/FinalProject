<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import TextAlign from '@tiptap/extension-text-align';
import BulletList from '@tiptap/extension-bullet-list';
import OrderedList from '@tiptap/extension-ordered-list';
import ListItem from '@tiptap/extension-list-item';
import ImageResize from 'tiptap-extension-resize-image'; // https://tiptap-resizable-image.vercel.app/getting-started
import { ref, onBeforeUnmount, defineProps, defineEmits } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    editable: {
        type: Boolean,
        default: true,
    }
});

const emit = defineEmits(['update:modelValue', 'imageUpload']);

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            bulletList: false,
            orderedList: false,
            listItem: false,
        }),
        Underline,
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                class: 'editor-link',
            },
        }),
        TextAlign.configure({
            types: ['heading', 'paragraph'],
            defaultAlignment: 'left',
        }),
        ImageResize,
        BulletList,
        OrderedList,
        ListItem,
    ],
    content: props.modelValue,
    editorProps: { // apply styles to the editor
        attributes: {
            class: 'editor-content',
        },
    },
    editable: props.editable,
    onUpdate: ({ editor, transaction }) => {
        const html = editor.getHTML();
        emit('update:modelValue', html);
    },
});

const isActive = (type, options = {}) => {
    return editor.value?.isActive(type, options) ?? false;
}

const toggleBold = () => editor.value?.chain().focus().toggleBold().run();
const toggleItalic = () => editor.value?.chain().focus().toggleItalic().run();
const toggleUnderline = () => editor.value?.chain().focus().toggleUnderline().run();
const toggleStrike = () => editor.value?.chain().focus().toggleStrike().run();

const toggleHeading = level => editor.value?.chain().focus().toggleHeading({ level: level }).run();
const toggleBulletList = () => editor.value?.chain().focus().toggleBulletList().run();
const toggleOrderedList = () => editor.value?.chain().focus().toggleOrderedList().run();

const alignLeft = () => editor.value?.chain().focus().setTextAlign('left').run();
const alignCenter = () => editor.value?.chain().focus().setTextAlign('center').run();
const alignRight = () => editor.value?.chain().focus().setTextAlign('right').run();

const setLink = () => {
    const url = window.prompt('URL');
    if (url) {
        editor.value?.chain().focus().setLink({ href: url }).run();
    } else {
        editor.value?.chain().focus().unsetLink().run();
    }
}

const fileInput = ref(null);
const uploadImage = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const tempUrl = URL.createObjectURL(file);

    editor.value?.chain().focus().setImage({ src: tempUrl }).run();

    emit('imageUpload', {
        file,
        tempUrl
    });
}

onBeforeUnmount(() => {
    editor.value?.destroy();
})
</script>

<template>
    <div :class="['editor-wrapper', { 'readonly-mode': !editable }]">
        <div class="editor-toolbar" v-if="editable">
            <div class="toolbar-group">
                <button type="button" @click="toggleBold" :class="{ active: isActive('bold') }" title="Bold">
                    B
                </button>
                <button type="button" @click="toggleItalic" :class="{ active: isActive('italic') }" title="Italic">
                    I
                </button>
                <button type="button" @click="toggleUnderline" :class="{ active: isActive('underline') }" title="Underline">
                    U
                </button>
                <button type="button" @click="toggleStrike" :class="{ active: isActive('strike') }" title="Strike">
                    S
                </button>
            </div>

            <div class="toolbar-group">
                <button type="button" @click="toggleHeading(1)" :class="{ active: isActive('heading', { level: 1 }) }" title="Heading 1">
                    H1
                </button>
                <button type="button" @click="toggleHeading(2)" :class="{ active: isActive('heading', { level: 2 }) }" title="Heading 2">
                    H2
                </button>
                <button type="button" @click="toggleHeading(3)" :class="{ active: isActive('heading', { level: 3 }) }" title="Heading 3">
                    H3
                </button>
            </div>

            <div class="toolbar-group">
                <button type="button" @click="toggleBulletList" :class="{ active: isActive('bulletList') }" title="Bullet List">
                    • List
                </button>
                <button type="button" @click="toggleOrderedList" :class="{ active: isActive('orderedList') }" title="Ordered List">
                    1. List
                </button>
            </div>

            <div class="toolbar-group">
                <button type="button" @click="alignLeft" :class="{ active: isActive('textAlign', { align: 'left' }) }" title="Align Left">
                    Left
                </button>
                <button type="button" @click="alignCenter" :class="{ active: isActive('textAlign', { align: 'center' }) }" title="Align Center">
                    Center
                </button>
                <button type="button" @click="alignRight" :class="{ active: isActive('textAlign', { align: 'right' }) }" title="Align Right">
                    Right
                </button>
            </div>

            <div class="toolbar-group">
                <button
                    type="button"
                    class="p-1 disabled:text-gray-400"
                    @click="editor?.chain().focus().undo().run()"
                    :disabled="!editor?.can().chain().focus().undo().run()"
                >
                    undo
                </button>
                <button
                    type="button"
                    @click="editor?.chain().focus().redo().run()"
                    :disabled="!editor?.can().chain().focus().redo().run()"
                    class="p-1 disabled:text-gray-400"
                >
                    redo
                </button>
            </div>

            <div class="toolbar-group">
                <button type="button" @click="setLink" :class="{ active: isActive('link') }" title="Link">
                    Link
                </button>
                <button type="button" @click="() => fileInput.click()" title="Upload Image">
                    Image
                </button>
                <input
                    ref="fileInput"
                    type="file"
                    accept="image/*"
                    class="hidden-input"
                    @change="uploadImage"
                />
            </div>
        </div>

        <EditorContent :editor="editor" />
    </div>
</template>

<style scoped>
.editor-wrapper {
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
    background-color: #f5f5f5;
    margin-bottom: 20px;
    width: 100%;
}

.readonly-mode {
    border: none;
    border-radius: 0;
    background-color: transparent;
}

.editor-toolbar {
    display: flex;
    flex-wrap: wrap;
    padding: 8px;
    border-bottom: 1px solid #ddd;
    background-color: #f5f5f5;
    gap: 8px;
}

.toolbar-group {
    display: flex;
    margin-right: 8px;
    border-right: 1px solid #ddd;
    padding-right: 8px;
}

.toolbar-group:last-child {
    border-right: none;
}

.editor-toolbar button {
    background-color: transparent;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 4px 8px;
    cursor: pointer;
    margin-right: 2px;
    font-size: 14px;
}

.editor-toolbar button:hover {
    background-color: #e9e9e9;
}

.editor-toolbar button.active {
    background-color: #e0e0e0;
    font-weight: bold;
}

.hidden-input {
    display: none;
}

.editor-content {
    padding: 16px;
    min-height: 400px;
}

/* Custom styles for the editor content */

:deep(.ProseMirror p) {
    margin-bottom: 0.75em;
}

:deep(.ProseMirror) {
    outline: none;
    min-height: 400px; /* Match the container height */
    padding: 20px; /* More padding for content */
    font-size: 16px; /* Slightly larger text for better readability */
    line-height: 1.6; /* Improved line spacing */
    box-shadow: inset 0 0 0 2px rgba(0, 119, 204, 0.2);
    background-color: #ffffff;
}

.readonly-mode :deep(.ProseMirror) {
    box-shadow: none;
    padding: 0;
    background-color: transparent;
    min-height: auto;
}

:deep(.ProseMirror h1) {
    font-size: 1.5em;  /* Larger than h2 (1.3em) */
    font-weight: bold;
    margin-bottom: 0.5em;
}

:deep(.ProseMirror h2) {
    font-size: 1.3em;
    font-weight: bold;
    margin-bottom: 0.5em;
}

:deep(.ProseMirror h3) {
    font-size: 1.1em;
    font-weight: bold;
    margin-bottom: 0.5em;
}

:deep(.ProseMirror img) {
    max-width: 100%;
    height: auto;
}

:deep(.editor-link) {
    color: #0077cc;
    text-decoration: underline;
}

:deep(.ProseMirror ul) {
    padding-left: 1.5em;
    list-style-type: disc;
}

:deep(.ProseMirror ol) {
    padding-left: 1.5em;
    list-style-type: decimal;
}

:deep(.ProseMirror li) {
    margin-bottom: 0.5em;
}
</style>
