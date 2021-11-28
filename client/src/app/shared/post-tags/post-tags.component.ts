import {Component, Input} from "@angular/core";

@Component({selector: 'post-tags', templateUrl: 'post-tags.component.html', styleUrls: ['post-tags.component.scss']})
export class PostTagsComponent {

  @Input("tags") tags: string[];
}
