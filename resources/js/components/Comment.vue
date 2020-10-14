<template>
    <li class="list-group-item">
        <b>{{ comment.name }}</b> said {{ comment.comment }}
        <a @click="replyToComment = comment" v-if="comment.depth!=3" class="reply-btn">Reply</a>
        <comment-form v-if="replyToComment == comment" :comment="comment"></comment-form>
        <comment-listzz v-if="collection[comment.id]" v-bind:comments="collection[comment.id]" v-bind:collection="collection"></comment-listzz>
    </li>
</template>
<script>
    import CommentList from './CommentList.vue';
    import CommentForm from './CommentForm.vue';

    export default{
        props: ['comment', 'collection'],
        data(){
            return {
                replyToComment: false
            }
        },
        mounted(){
        },
        components: {
            'comment-listzz': CommentList,
            'comment-form': CommentForm
        }
    }
</script>
