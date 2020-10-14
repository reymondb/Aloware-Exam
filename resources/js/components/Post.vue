<template>
	<div class="panel panel-default">
		<div class="panel-heading post-heading">What's there in your mind <input type="text" name="name" v-model="name" placeholder="Name" ref="name"> ?
			</div>
		<div class="panel-body">
			<div class="form-group" v-bind:class='[ errors.hasOwnProperty("content") ? "has-error" : "" ]'>
				<textarea v-model="content" name="content" rows="3" class="form-control status" ref="status"></textarea>
				<span v-if='[errors.hasOwnProperty("content")]' class="help-block" v-for="error in errors.content">{{ error }}</span>
			</div>
		</div>
		<div class="panel-footer">
			<button class="btn btn-info post-btn" @click="postthis"> Post</button>
			<div class="clearfix"></div>
		</div>		
		<div style="overflow: auto;">
			<div class="panel panel-default" v-for="post in posts">
				<div class="panel-heading status-header">
					{{ post.name }}  posted:
				</div>
				<div class="panel-body">
					<h4 class="post">
						{{ post.content }}
					</h4>
				</div>
				<div class="panel-footer">
					<div class="col-md-12">
						<input type="text" class="form-control"  v-model="post.replyname" placeholder="Name" 
						v-on:keyup.enter="$event.target.nextElementSibling.focus()" :ref="'n' + post.id" required>
						<input type="text" class="form-control" v-model="post.reply" placeholder="leave a comment..." :ref="'r' + post.id"
						v-on:keyup.enter="comment(post)" required>
					</div>
					<div class="col-md-12">
						<comment-lists v-if="post.comments" :collection="post.comments" :comments="post.comments.root"></comment-lists>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</template>
<style scoped>
	textarea{
		resize: vertical;
	}
</style>
<script>
	import CommentList from './CommentList.vue';
	export default{
		data(){
			return {
				name: '',
				content: '',
				errors: [],
				post: {
					replyname:'',
					reply: '',
					errorname:false,
					errorreply:false
				},
			}
		},
		computed: {
			posts(){
				return this.$store.state.posts;
			}
		},
		components: {
			'comment-lists': CommentList
		},
		mounted(){
			this.getPosts();
		},
		methods:{
			postthis(){
				if(!this.name){
					alert("Name is required."); 
                    this.$refs.name.focus();
                    return false;
				}
				if(!this.content){
					alert("Status is required."); 
					this.$refs.status.focus();
                    return false;
				}
				axios.post('/post',{name: this.name,content: this.content}).then(response => {
					if(!response.data.error){
						this.name = '';
						this.content = '';
						this.$store.commit('pushPost',response.data.data);
					}else{
						this.errors = response.data.data;
					}
				});
			},
			
			getPosts(){
				axios.get('/posts').then(response => {
					if(!response.data.error){
						response.data.data.forEach((post) => {
							this.$store.commit('pushPost',post);
						});
					}
				});
			},
                
			comment(post){
				if(!post.replyname){
					post.errorname=true;
					alert("Name is required."); 
					this.$refs['n' + post.id][0].focus();
					return post.errorname;
				}
				else{
					post.errorname=false;
				}
				if(!post.reply){
					post.errorreply=true;
					alert("Comment is required."); 
					this.$refs['r' + post.id][0].focus();
					return post.errorreply;
				}
				else{
					post.errorreply=false;
				}
				if(post.errorreply==false && post.errorname==false){
					axios.post('/replycomment', {name:post.replyname,content: post.reply, post_id: post.id}).then(response => {
						if (!response.data.error) {
							post.reply = '';
							post.replyname = '';
							let payLoad = {
								post_id: post.id,
								comments: response.data.data
							};
							this.$store.commit('updateComments',payLoad);
						}
					});
				}
				
			}
			
		}
	}
</script>