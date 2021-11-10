export class Comment {
  id: string;
  author: string;
  body: string;
  createdAt: string[];
  parentId: string | null;
  postId: string;
}
