import {Component, Input} from "@angular/core";
import {Comment} from "@app/_models/comment";
import {Post} from "@app/_models";

@Component({selector: 'comment', templateUrl: 'comment.component.html', styleUrls: ['comment.component.scss']})
export class CommentComponent {

  @Input("comment") comment: Comment;
  @Input("postId") postId: string;
  @Input("onReply") onReply: (postId: string, commentId: string) => void;

}
