export class CommentWithPost {
  id: string;
  author: string;
  body: string;
  createdAt: string;
  parentId: string | null;
  postId: string;
  postTitle: string;
  postTags: string[];
}
