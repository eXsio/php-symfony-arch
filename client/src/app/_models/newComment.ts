export class NewComment {
  postId: string;
  author: string;
  body: string;
  parentId: string | null;


  constructor(postId: string, author: string, body: string, parentId: string | null) {
    this.postId = postId;
    this.author = author;
    this.body = body;
    this.parentId = parentId;
  }
}
