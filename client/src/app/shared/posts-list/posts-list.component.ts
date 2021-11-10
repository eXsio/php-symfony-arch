import {Component, Input} from '@angular/core';

import {Page, PostHeader, User} from '@app/_models';

@Component({selector: 'posts-list', templateUrl: 'posts-list.component.html', styleUrls: ['posts-list.component.scss']})
export class PostsListComponent {
  @Input("posts") posts: Page<PostHeader>;
  @Input("user") user: User;
  @Input("onDelete") onDelete: (id: string) => void = null;

  constructor() {
  }


  delete(id: string) {
    if (this.onDelete != null) {
      this.onDelete(id);
    }
  }
}
