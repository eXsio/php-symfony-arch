import {Comment} from "@app/_models/comment";

export class Post {
  id: string;
  title: string;
  body: string;
  comments: Array<Comment>;
  createdById: string;
  createdByName: string;
  createdAt: string;
  tags: string[];
}
