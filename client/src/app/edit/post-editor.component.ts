import {Component} from "@angular/core";
import {PostService} from "@app/_services";
import {ActivatedRoute, Router} from "@angular/router";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {NewPost} from "@app/_models";
import {first} from "rxjs/operators";
import {MatSnackBar} from "@angular/material/snack-bar";
import {COMMA, ENTER} from '@angular/cdk/keycodes';
import {MatChipInputEvent} from "@angular/material/chips";

@Component({
  selector: 'post-editor',
  templateUrl: 'post-editor.component.html',
  styleUrls: ['post-editor.component.scss']
})
export class PostEditorComponent {

  postId: string | null = null;
  form: FormGroup;
  body: string = '';
  error = '';
  loading = true;
  readonly separatorKeysCodes: number[] = [ENTER, COMMA];

  constructor(private postService: PostService,
              private route: ActivatedRoute,
              private formBuilder: FormBuilder,
              private snackBar: MatSnackBar,
              private router: Router) {

  }

  ngOnInit() {
    this.form = this.formBuilder.group({
      title: ['', Validators.required],
      body: ['', Validators.required],
      tags: [[]]
    });
    this.route.params.subscribe(params => {
      this.postId = params['postId'];
      if (this.postId != null) {
        this.loadPost();
      } else {
        this.loading = false;
      }
    });
  }

  private loadPost() {
    this.postService.getPost(this.postId)
      .subscribe(post => {
        this.body = post.body;
        this.form.patchValue({
          'title': post.title,
          'body': post.body,
          'tags': post.tags
        });
        this.loading = false;
      })
  }

  addTag(event: MatChipInputEvent): void {
    const input = event.chipInput;
    const value = event.value;

    if ((value || '').trim()) {
      this.tags.setValue([...this.tags.value, value.trim()]);
      this.tags.updateValueAndValidity();
    }

    // Reset the input value
    if (input) {
      input.clear();
    }
  }

  removeTag(tag: string): void {
    const index = this.tags.value.indexOf(tag);

    if (index >= 0) {
      this.tags.value.splice(index, 1);
      this.tags.updateValueAndValidity();
    }
  }

  get tags() {
    return this.form.get('tags');
  }


  save() {
    if (this.form.invalid) {
      return;
    }
    const newPost = new NewPost(
      this.form.controls.title.value,
      this.form.controls.body.value,
      this.form.controls.tags.value,
    );
    if (this.postId != null) {
      console.log('updating post ' + this.postId);
      console.log(newPost);
      this.postService.updatePost(this.postId, newPost)
        .pipe(first())
        .subscribe({
          next: (response) => {
            this.snackBar.open('Post updated', '', {
              duration: 2000
            });
            this.router.navigate(['/post/' + this.postId]);
          },
          error: error => {
            this.snackBar.open('Error while updating Post: ' + error, '', {
              duration: 2000
            });
          }
        });
    } else {
      console.log('creating new post ');
      console.log(newPost);
      this.postService.createPost(newPost)
        .pipe(first())
        .subscribe({
          next: (response) => {
            console.log("comment created with id: " + response.id);
            this.snackBar.open('New Post posted', '', {
              duration: 2000
            });
            this.router.navigate(['/post/' + response.id]);
          },
          error: error => {
            this.snackBar.open('Error while creating new Post: ' + error, '', {
              duration: 2000
            });
          }
        });
    }
  }
}
